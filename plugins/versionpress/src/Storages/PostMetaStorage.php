<?php

namespace VersionPress\Storages;

use Nette\Utils\Strings;
use VersionPress\ChangeInfos\PostMetaChangeInfo;
use VersionPress\Utils\ArrayUtils;

class PostMetaStorage extends MetaEntityStorage {
    function __construct(PostStorage $storage) {
        parent::__construct($storage, 'meta_key', 'meta_value', 'vp_post_id');
    }

    protected function createChangeInfoWithParentEntity($oldEntity, $newEntity, $oldParentEntity, $newParentEntity, $action) {
        $postTitle = ArrayUtils::getFieldFromFirstWhereExists('post_title', $newParentEntity, $oldParentEntity);
        $postType = ArrayUtils::getFieldFromFirstWhereExists('post_type', $newParentEntity, $oldParentEntity);
        $postVpId = ArrayUtils::getFieldFromFirstWhereExists('vp_id', $newParentEntity, $oldParentEntity);

        $vpId = ArrayUtils::getFieldFromFirstWhereExists('vp_id', $newEntity, $oldEntity);
        $metaKey = ArrayUtils::getFieldFromFirstWhereExists('meta_key', $newEntity, $oldEntity);

        return new PostMetaChangeInfo($action, $vpId, $postType, $postTitle, $postVpId, $metaKey);
    }

    public function shouldBeSaved($data) {
        $ignoredMeta = array(
            '_edit_lock',
            '_edit_last',
            '_pingme',
            '_encloseme'
        );

        return !in_array($data['meta_key'], $ignoredMeta);
    }
}
