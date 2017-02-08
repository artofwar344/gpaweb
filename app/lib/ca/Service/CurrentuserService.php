<?php
namespace Ca\Service;

use \DB,
	\Auth,
	Ca\DocumentStatus,
	Ca\DocumentSource;

class CurrentUserService {

	public static $user;
	public static $user_id;

	public static function top_level_documents()
	{
		return DB::table('document')
			->whereNull('parentid')
			->where('userid', '=', self::$user_id)
			->where('status', '!=', \Ca\DocumentStatus::deleted)
			->where('from', '!=', \Ca\DocumentSource::favorite)
			->orderBy('type', 'desc')->get();
	}

	public static function documents_by_parent_id($folderid)
	{
		return DB::table('document')
			->where('parentid', '=', $folderid)
			->where('status', '!=', \Ca\DocumentStatus::deleted)
			->where('from', '!=', \Ca\DocumentSource::favorite)
			->orderBy('type', 'desc')
			->get();
	}

	public static function is_owner($documentId, $userid)
	{
		return DB::table('document')
			->where('documentid', '=', $documentId)
			->where('userid', '=', $userid)
			->count() > 0;
	}
}

CurrentUserService::$user = !Auth::guest() ? Auth::user() : null;
CurrentUserService::$user_id = empty(CurrentUserService::$user) ? null : CurrentUserService::$user->userid;