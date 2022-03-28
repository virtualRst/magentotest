<?php
namespace Rst\Magentotest\Api;

interface AllnewsRepositoryInterface
{
	public function save(\Rst\Magentotest\Api\Data\AllnewsInterface $news);

    public function getById($newsId);

    public function delete(\Rst\Magentotest\Api\Data\AllnewsInterface $news);

    public function deleteById($newsId);
}
?>
