<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Lang;

class StatusController extends Controller {

    public static function licenseLabel($status, $locale = null) {
        if ($status == 1) {
            return Lang::get('status.approved_license', [], $locale);
        } else if ($status == 3) {
            return Lang::get('status.rejected_license', [], $locale);
        } else {
            return false;
        }
    }

    public static function vehicleLabel($status, $locale = null) {
        if ($status == 1) {
            return Lang::get('status.approved_vehicle', [], $locale);
        } else if ($status == 3) {
            return Lang::get('status.rejected_vehicle', [], $locale);
        } else {
            return false;
        }
    }


    public static function statusLabelRT($status, $locale = null) {
        return Lang::get('statuses/route.'.$status.'.label', [], $locale);
    }

    public static function statusLabelVU($status, $locale = null) {
        if ($status == 1) {
            return Lang::get('status.active', [], $locale);
        } else if ($status == 2) {
            return Lang::get('status.pending', [], $locale);
        } else if ($status == 3) {
            return Lang::get('status.suspended', [], $locale);
        } else if ($status == 4) {
            return Lang::get('status.refunded', [], $locale);
        } else if ($status == 0) {
            return Lang::get('status.inactive', [], $locale);
        } else {
            return false;
        }
    }

    public static function statusLabel($status, $locale = null) {
        if ($status == 1) {
            return Lang::get('status.active', [], $locale);
        } else if ($status == 2) {
            return Lang::get('status.pending', [], $locale);
        } else if ($status == 3) {
            return Lang::get('status.rejected', [], $locale);
        } else if ($status == 4) {
            return Lang::get('status.refunded', [], $locale);
        } else if ($status == 0) {
            return Lang::get('status.inactive', [], $locale);
        } else {
            return false;
        }
    }

    public static function statusLabelClass($status) {
        if ($status == 1) {
            return 'success';
        } else if ($status == 2) {
            return 'warning';
        } else if ($status == 3) {
            return 'danger';
        } else if ($status == 4) {
            return 'warning';
        } else if ($status == 5) {
            return 'warning';
        } else if ($status == 6) {
            return 'warning';
        } else if ($status == 0) {
            return 'danger';
        } else {
            return false;
        }
    }

    public static function statusSaleLabelClass($status) {
        if ($status == 1) {
            return 'success';
        } else if ($status == 2) {
            return 'warning';
        } else if ($status == 3) {
            return 'success';
        } else if ($status == 4) {
            return 'danger';
        } else if ($status == 5) {
            return 'warning';
        } else if ($status == 6) {
            return 'warning';
        } else if ($status == 0) {
            return 'danger';
        } else {
            return false;
        }
    }

    public static function statusFaIcon($status) {
        if ($status == 1) {
            return 'fa-check';
        } else if ($status == 2) {
            return 'fa-clock-o';
        } else if ($status == 3) {
            return 'fa-times';
        } else if ($status == 4) {
            return 'fa-money';
        } else if ($status == 0) {
            return 'fa-times';
        } else {
            return false;
        }
    }


    public static function fetchText($status, $texts = null) {
        foreach($status as $key => $val) {
            $data[$key]['id'] = $key;
            $data[$key]['name'] = $val ?? self::statusLabel($key);
        }
        return $data;
    }


    public static function fetch($status, $url = null, array $urlFor = [2], array $texts = [], $inverted = null, $type = null) {
        $data = [
            'text' => $texts[$status]['text'] ?? self::statusLabel($status),
            'icon' => $texts[$status]['icon'] ?? self::statusFaIcon($status),
            'class' => $texts[$status]['label'] ?? self::statusLabelClass($status),
        ];
        if (isset($url)) {
            $data['link'] = $url;
        }
        if (!empty($inverted)) {
            $data['status_label'] = $inverted;
        }
        if (in_array($status, $urlFor)) {
            $data['url'] = $url;
        }

        return view(($type == 'admin') ? 'components.status-admin' : 'components.status', $data)->render();

    }

    public static function fetchWithoutText($status, array $texts = [], $inverted = null, $type = null) {
        $data = [
            'icon' => $texts[$status]['icon'] ?? self::statusFaIcon($status),
            'class' => $texts[$status]['label'] ?? self::statusLabelClass($status),
        ];
        return view(($type == 'admin') ? 'components.status-admin' : 'components.status', $data)->render();

    }
}
