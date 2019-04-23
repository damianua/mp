<?php
/**
 * @var \App\User $user
 */
$user = $user ?? Auth::user();
?>
<b>{{ $user->name }}</b> ({{ __('user.group_'.$user->group_id) }})
