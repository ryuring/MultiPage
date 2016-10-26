<?php
/**
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright (c) baserCMS Users Community <http://basercms.net/community/>
 *
 * @copyright		Copyright (c) baserCMS Users Community
 * @link			http://basercms.net baserCMS Project
 * @package			MultiPage.Model
 * @since			baserCMS v 4.0.0
 * @license			http://basercms.net/license/index.html
 */

/**
 * MultiPage
 *
 * @package MultiPage.MultiPage
 */
class MultiPage extends AppModel {

/**
 * Behavior Setting
 *
 * @var array
 */
	public $actsAs = array('BcContents');

/**
 * バリデーション
 *
 * @var array
 */
	public $validate = array(
		'content' => array(
			array('rule' => array('notBlank'),
				'message' => 'コンテンツ内容を入力してください。',
				'required' => true)
		)
	);

/**
 * ページをコピーする
 *
 * @param $id
 * @param $title
 * @param $authorId
 * @return bool|mixed
 */
	public function copy($id, $newTitle, $newAuthorId, $newSiteId = null) {
		$data = $this->find('first', array('conditions' => array('MultiPage.id' => $id)));
		if(!$data) {
			return false;
		}
		unset($data['MultiPage']['id']);
		unset($data['MultiPage']['modified']);
		unset($data['MultiPage']['created']);
		$this->getDataSource()->begin();
		if ($this->save($data['MultiPage'])) {
			$data = $this->Content->copy($data['Content']['id'], $this->id, $newTitle, $newAuthorId, $newSiteId);
			if($data) {
				$this->getDataSource()->commit();
				return $data;
			}
		}
		$this->getDataSource()->rollback();
		return false;
	}

}