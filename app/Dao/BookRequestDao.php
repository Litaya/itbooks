<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2017/11/21
 * Time: 下午10:40
 */

namespace App\Dao;

use App\Libraries\WechatModules\Book;
use App\Models\BookRequest;
use App\Models\User;

class BookRequestDao{

	public static $UNHANDLED      = 0;
	public static $SUCCESS        = 1;
	public static $FAIL_REPEATED  = 2;

	public static $UNHANDLED_MSG       = "未处理";
	public static $PASS_SUCCESS_MSG    = "成功通过样书申请";
	public static $FAIL_REPEATED_MSG   = "此申请已被审批过";
	public static $REG_SUCCESS_MSG     = "成功拒绝该样书申请";
	public static $DESTROY_SUCCESS_MSG = "成功删除样书申请记录";
	public static $RESET_SUCCESS_MSG   = "成功重置样书申请记录的状态";
	public static $PASS_BINDORDER_SUCCESS_MSG = "成功通过样书申请并绑定快递单号";

	/**
	 * 通过用户的样书申请
	 * @param BookRequest $bookreq
	 * @param User $admin
	 * @param User $user
	 * @return array
	 */
	public static function passBookRequest(BookRequest $bookreq, User $admin, User $user){
		if($bookreq->status == 0){
			$bookreq->status = 1;
			$bookreq->handler_id = $admin->id;
			$bookreq->update();
			return [
				"status"  => BookRequestDao::$SUCCESS,
				"message" => BookRequestDao::$PASS_SUCCESS_MSG
			];
		}
		else
			return [
				"status"  => BookRequestDao::$FAIL_REPEATED,
				"message" => BookRequestDao::$FAIL_REPEATED_MSG
			];
	}

	/**
	 * @param BookRequest $bookreq
	 * @param User $admin
	 * @param User $user
	 * @param null $message
	 * @return array
	 */
	public static function rejectBookRequest(BookRequest $bookreq, User $admin, User $user, $message = null){
		if($bookreq->status == 0){
			$bookreq->status = 2;
			$bookreq->handler_id = $admin->id;
			$js = json_decode($bookreq->message, true);
			$js["admin_reply"] = $message;
			$bookreq->message = json_encode($js);
			$bookreq->update();
			$user_result = UserDao::updateBookRequestLimit($user, 1);
			if($user_result["status"] != UserDao::$SUCCESS)
				return $user_result;
			$result = [
				"status"  => BookRequestDao::$SUCCESS,
				"message" => BookRequestDao::$REG_SUCCESS_MSG
			];
		}
		else
			$result = [
				"status"  => BookRequestDao::$FAIL_REPEATED,
				"message" => BookRequestDao::$FAIL_REPEATED_MSG
			];
		return $result;
	}

	/**
	 * @param BookRequest $bookreq
	 * @param User $user
	 * @return array|null
	 */
	public static function destroyBookRequest(BookRequest $bookreq, User $user){
		$user_result = null;
		if($bookreq->status == 2) {
			$user_result = UserDao::updateBookRequestLimit($user, -1); // 给用户的样书申请-1
		}
		$bookreq->delete();
		if (($user_result != null && $user_result["status"] != UserDao::$SUCCESS)) {
			return $user_result;
		}
		return [
			"status"  => BookRequestDao::$SUCCESS,
			"message" => BookRequestDao::$DESTROY_SUCCESS_MSG
		];
	}

	/**
	 * @param BookRequest $bookreq
	 * @param User $admin
	 * @param User $user
	 * @return array
	 */
	public static function resetBookRequest(BookRequest $bookreq, User $admin, User $user){
		if($bookreq->status == 2){
			$user_result = UserDao::updateBookRequestLimit($user, -1);
			if($user_result['status'] != UserDao::$SUCCESS){
				return $user_result;
			}
		}
		if($bookreq->status != 0){
			$bookreq->status = 0;
			$bookreq->order_number = "";
			$bookreq->handler_id = $admin->id;
			$bookreq->update();
		}
		return [
			"status"  => BookRequestDao::$SUCCESS,
			"message" => BookRequestDao::$RESET_SUCCESS_MSG
		];
	}

	/**
	 * @param BookRequest $bookreq
	 * @param User $admin
	 * @param User $user
	 * @param $order_number
	 * @return array
	 */
	public static function passAndBindOrder(BookRequest $bookreq, User $admin, User $user, $order_number){
		$result = BookRequestDao::passBookRequest($bookreq, $admin, $user);
		if($result["status"]!=BookRequestDao::$SUCCESS)
			return $result;

		$bookreq->order_number = $order_number;
		$bookreq->update();
		return [
			"status"  => BookRequestDao::$SUCCESS,
			"message" => BookRequestDao::$PASS_BINDORDER_SUCCESS_MSG
		];
	}
}