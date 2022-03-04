<?php

namespace App\Traits\Models;

use App\Models\Post as PostModel;

trait Post
{
    /**
     * @var PostModel
     */
    protected $_postModel;

    /**
     * @return PostModel
     */
    public function getPostModel(): PostModel
    {
        if (!$this->_postModel) {
            $this->setPostModel($this->_createDefaultPostModel());
        }

        return $this->_postModel;
    }

    /**
     * @param PostModel $postModel
     * @return void
     */
    public function setPostModel(PostModel $postModel): void
    {
        $this->_postModel = $postModel;
    }

    /**
     * @return PostModel
     */
    protected function _createDefaultPostModel(): PostModel
    {
        return new PostModel();
    }
}
