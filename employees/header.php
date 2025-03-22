<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : "Default Title"; ?></title>
    <?php if (isset($WithEmployeeCSS) && $WithEmployeeCSS): ?>
        <link rel="stylesheet" href="employee_css.css">
    <?php endif; ?>

	<?php if (isset($WithoutEmployeeCSS) && $WithoutEmployeeCSS): ?>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<style>
			::-webkit-scrollbar {
				width: 12px;
				height: 12px;
			}

			::-webkit-scrollbar-thumb {
				background-color: #007bff;
				border-radius: 10px;
			}

			::-webkit-scrollbar-thumb:hover {
				background-color: #0056b3;
			}

			::-webkit-scrollbar-track {
				background-color: #f1f1f1;
				border-radius: 10px;
			}
		</style>
	<?php endif; ?>

    <link rel="icon" type="image/x-icon" href="images/PaterosLogo.png">
</head>

