<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 25 Jul 2019 21:37:12 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class OauthPersonalAccessClient
 * 
 * @property int $id
 * @property int $client_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class OauthPersonalAccessClient extends Eloquent
{
	protected $casts = [
		'client_id' => 'integer'
	];

	protected $fillable = [
		'client_id'
	];
}
