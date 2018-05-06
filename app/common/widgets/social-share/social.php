<?php 

	if (in_array('twitter', $this->networks)) {
		$this->getView()->registerMetaTag([
		    'name' => 'twitter:card',
		    'content' => 'summary_large_image',
		]);
		$this->getView()->registerMetaTag([
		    'name' => 'twitter:site',
		    'content' => $this->via_twitter ?? '',
		]);
		$this->getView()->registerMetaTag([
		    'name' => 'twitter:title',
		    'content' => $this->title ?? '',
		]);
		$this->getView()->registerMetaTag([
		    'name' => 'twitter:description',
		    'content' => substr($this->description ?? '', 0, 150) . '...',
		]);
		$this->getView()->registerMetaTag([
		    'name' => 'twitter:image',
		    'content' => $this->image_url ?? '',
		]);
		$this->getView()->registerMetaTag([
		    'name' => 'twitter:url',
		    'content' => $this->url ?? '',
		]);
		$this->getView()->registerMetaTag([
		    'name' => 'twitter:hashtags',
		    'content' => $this->hashtags ?? '',
		]);
	}

	echo $html;
