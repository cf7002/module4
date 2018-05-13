<?php

namespace lib;

class UploadedFile
{
    private $name;
    private $type;
    private $size = [];

    /**
     * UploadedFile constructor.
     *
     * @param array $upload_file
     *
     * @throws \Exception
     */
    public function __construct($upload_file)
    {
        /** @var Session $session */
        $session = Session::getInstance();

        $uploads = Config::get('uploads');

        do {
            if (!is_dir(UPLOAD_DIR)) {
                throw new \Exception("Отсутствует доступ к каталогу для загрузки файлов " . UPLOAD_DIR);
            }

            if ($upload_file['error'] !== 0) {
                $session->setFlash($this->checkError($upload_file['error']), 'warning');
                break;
            }

            if ($upload_file['size'] > $uploads['max_file_size']) {
                $session->setFlash("Размер файла превышает допустимый.", 'warning');
                break;
            }

            if (!$this->isImage($upload_file["tmp_name"])) {
                $session->setFlash("Недопустимый тип файла.", 'warning');
                break;
            }

            $this->name = $this->generateFilename($upload_file["name"]);

            if (file_exists(UPLOAD_DIR . $this->name)) {
                $session->setFlash("Ошибка сохранения файла.", 'warning');
                break;
            }

            if (!$this->upload(UPLOAD_DIR, $upload_file["tmp_name"])) {
                $err_file = htmlspecialchars($upload_file['name']);
                $session->setFlash("Не удалось сохранить файл {$err_file}.", 'warning');
                return null;
            }
        } while (0);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $err_code
     *
     * @return null|string
     */
    private function checkError($err_code)
    {
        switch ($err_code) {
            case 0:
                $message = null;
                break;
            case 1:
                $message = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
                break;
            case 2:
                $message = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
                break;
            case 3:
                $message = 'The uploaded file was only partially uploaded.';
                break;
            case 4:
                $message = 'No file was uploaded.';
                break;
            case 6:
                $message = 'Missing a temporary folder.';
                break;
            case 7:
                $message = 'Failed to write file to disk.';
                break;
            case 8:
                $message = 'A PHP extension stopped the file upload.';
                break;
            default:
                $message = 'Undefined error.';
        }

        return $message;
    }

    /**
     * @param string $file
     *
     * @return bool
     */
    private function isImage($file)
    {
        $file_info = getimagesize($file);

        if ($file_info === false) {
            return false;
        }

        $this->size = [$file_info[0], $file_info[1]];
        $this->type = $file_info['mime'];

        return true;
    }

    /**
     * @param string $file_name
     *
     * @return string
     */
    private function generateFilename($file_name)
    {
        $name = uniqid();
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);

        return md5($name) . '.' . strtolower($ext);
    }

    /**
     * @param string $upload_path
     * @param string $file
     *
     * @return bool
     */
    private function upload($upload_path, $file)
    {
         return move_uploaded_file($file, $upload_path . $this->name);
    }
}