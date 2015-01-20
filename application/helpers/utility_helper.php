<?php

function asset_url($asset = '') {
   return base_url() . 'assets/' .  ltrim($asset, '/');
}