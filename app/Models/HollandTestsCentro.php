<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 09 Jun 2020 12:24:46 -0600.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class HollandTestsCentro
 * 
 * @property int $id
 * @property int $centro_id
 * @property int $test_id
 * 
 * @property \App\Models\Centro $centro
 * @property \App\Models\HollandTest $holland_test
 *
 * @package App\Models
 */
class HollandTestsCentro extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'centro_id' => 'integer',
		'test_id' => 'integer'
	];

	protected $fillable = [
		'centro_id',
		'test_id'
	];

	public function centro()
	{
		return $this->belongsTo(\App\Models\Centro::class);
	}

	public function holland_test()
	{
		return $this->belongsTo(\App\Models\HollandTest::class, 'test_id');
	}
}
