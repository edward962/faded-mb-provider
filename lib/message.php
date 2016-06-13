<?php
if ( isset($_SESSION['flash']) && is_array($_SESSION['flash']) ) {
	$type = isset($_SESSION['flash']['type']) ? $_SESSION['flash']['type'] : 'danger';
	$message = isset($_SESSION['flash']['message']) ? $_SESSION['flash']['message'] : 'Oops, something went wrong.';
	switch ( $type ) {
        case 'success':
            $icon = 'check-circle';
        break;
        case 'info':
            $icon = 'info-circle';
        break;
        case 'warning':
            $icon = 'exclamation-triangle';
        break;
        case 'danger':
            $icon = 'exclamation-circle';
        break;
        default:
            $icon = 'exclamation-circle';
    }
}
?>
<?php if ( isset($type) && isset($message) ) : ?>
		<div data-ng-init="showAlert('<?php echo $type; ?>!','<?php echo $message; ?>')"></div>
<?php endif; ?>




