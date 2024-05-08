<header class="header">
	<div class="header__container">
		<div class="header__logo logo">
			<span class="logo__title"><a class="logo__link" href="/">Нарушениям.Нет</a></span>

		</div>
		<nav class="header__nav">
			<ul class="header__menu menu">
				<?php if (!empty($_SESSION['is_admin'])) : ?>
				<li class="menu__item"><a href="admin.php" class="menu__link">Админ</a></li>
				<?php endif; ?>
				<?php if (!empty($_SESSION['user_id'])) : ?>
				<li class="menu__item"><a href="neworder.php" class="menu__link">Написать завление</a></li>
				<li class="menu__item"><a href="orders.php" class="menu__link">Заявления</a></li>
				<li class="menu__item"><a href="profile.php" class="menu__link">Личный кабинет</a></li>
				<li class="menu__item"><a href="logout.php" class="menu__link">Выйти</a></li>
				<?php else : ?>
				<li class="menu__item"><a href="login.php" class="menu__link">Вход</a></li>
				<li class="menu__item"><a href="register.php" class="menu__link">Регистрация</a></li>
				<?php endif; ?>
			</ul>
		</nav>
	</div>
</header>