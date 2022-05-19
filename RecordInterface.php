<?php

interface RecordInterface
{
	public function getISBN(): string;

	public function getTitle(): string;

	public function getAuthor(): string;

	public function getCreatedDate(): DateTime;

	public function getType(): string;

	public function setISBN(string $isbn);

	public function setTitle(string $title);

	public function setAuthor(string $author);

	public function setCreatedDate(DateTime $createdDate);

	public function setType(string $type);

	public function save();

	public static function getRecord(string $id);
}