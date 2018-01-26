<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2018/1/26
 * Time: 下午1:10
 */

namespace App\Helpers\ExportInvoice;

use App\Models\User;

abstract class ExportInvoiceHelper{
	protected $records;
	public abstract function constructExportRecords(User $user);
	public abstract function exportRecords($records);
}
