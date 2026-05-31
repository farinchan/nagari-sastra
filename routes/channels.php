<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// CRM notifications — only super-admin and marketing can listen
Broadcast::channel('crm.notifications', function ($user) {
    return $user->hasRole('super-admin') || $user->hasRole('marketing');
});
