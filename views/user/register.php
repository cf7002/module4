<form class="form-signun" method="post">
    <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">Регистрация</h1>
    </div>

    <div class="form-label-group">
        <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Электронный адрес" required autofocus>
        <label for="inputEmail">Электронный адрес</label>
    </div>

    <div class="form-label-group">
        <input type="text" id="inputFirstName" class="form-control" name="first_name" placeholder="Имя" required>
        <label for="inputFirstName">Имя</label>
    </div>

    <div class="form-label-group">
        <input type="text" id="inputLastName" class="form-control" name="last_name" placeholder="Фамилия" required>
        <label for="inputLastName">Фамилия</label>
    </div>

    <div class="form-label-group">
        <input type="text" id="inputNickName" class="form-control" name="nick_name" placeholder="Псевдоним" required>
        <label for="inputNickName">Псевдоним</label>
    </div>

    <div class="form-label-group">
        <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Пароль" required>
        <label for="inputPassword">Пароль</label>
    </div>

    <input type="hidden" id="inputVersion" class="form-control" name="version" value="1">

    <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="1">Зарегистрировать</button>
</form>