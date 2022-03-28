<?php
namespace Rst\Magentotest\Api\Data;

interface AllnewsInterface
{
	const NEWS_ID = 'news_id';
	const TITLE  = 'title';
	const DESCRIPTION = 'description';
	const STATUS = 'status';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';

	public function getId();

	public function getTitle();

	public function getDescription();

	public function getStatus();

	public function getCreatedAt();

	public function getUpdatedAt();

	public function setId($id);

	public function setTitle($title);

	public function setDescription($description);

	public function setStatus($status);

	public function setCreatedAt($created_at);

	public function setUpdatedAt($updated_at);
}
?>
