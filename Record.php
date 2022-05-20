<?php
require_once 'RecordInterface.php';

class Record implements RecordInterface
{
	private const BASE_PATH = './catalog/';
	private string $isbn;
	private string $title;
	private string $author;
	private string $type;
	private DateTime $createdDate;
	private array $data;

	public function __construct(string $type, array $data = [])
	{
		if ($this->validateData($data)) {
			$this->type = ucwords($type);
			$this->isbn = $data['isbn'];
			unset($data['isbn']);
			$this->title = $data['title'];
			unset($data['title']);
			$this->author = $data['author'];
			unset($data['author']);
			$this->createdDate = $data['createdDate'];
			unset($data['createdDate']);
			$this->data = $data;
		}
	}

	public function delete(): bool
	{
		if (file_exists(self::BASE_PATH . "$this->type/$this->isbn")){
			return unlink(self::BASE_PATH . "$this->type/$this->isbn");
		}
		return true;
	}

	public function getISBN(): string
	{
		return $this->isbn;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getAuthor(): string
	{
		return $this->author;
	}

	public function getCreatedDate(): DateTime
	{
		return $this->createdDate;
	}

	public function save()
	{
		$file = self::BASE_PATH . $this->type . '/' . $this->isbn;
		if (!is_dir(self::BASE_PATH . $this->type)) {
			mkdir(self::BASE_PATH . $this->type, 0777, true);
		}

		$fp = fopen($file, 'w+');
		fwrite($fp, "isbn=$this->isbn\n");
		fwrite($fp, "title=$this->title\n");
		fwrite($fp, "author=$this->author\n");
		fwrite($fp, "createdDate={$this->createdDate->format('c')}\n");
		foreach ($this->data as $key => $value) {
			if (in_array($key, ['isbn', 'title', 'author', 'createdDate']) && !empty($value) && is_string($key)) {
				continue;
			}
			fwrite($fp, "$key=$value\n");
		}
		fclose($fp);
	}

	public function getType(): string
	{
		return $this->type;
	}

	public static function getRecord(string $id)
	{
		if (!is_dir(self::BASE_PATH)) {
			return false;
		}
		$types = array_diff(scandir(self::BASE_PATH), ['.', '..']);
		if (empty($types)) {
			return false;
		}
		foreach ($types as $type) {
			$file = self::BASE_PATH . "$type/$id";
			if (file_exists($file)) {
				$fp = fopen($file, 'r');
				$recordArray = [];
				foreach (explode("\n", fread($fp, filesize($file))) as $line) {
					if (!empty($line)) {
						$data = explode('=', $line);
						$recordArray[$data[0]] = $data[1];
					}
				}

				if (self::validateData($recordArray)) {
					return new self($type, $recordArray);
				}
			}
		}
		return false;
	}

	public function setISBN(string $isbn)
	{
		$this->isbn = $isbn;
	}

	public function setTitle(string $title)
	{
		$this->title = $title;
	}

	public function setAuthor(string $author)
	{
		$this->author = $author;
	}

	public function setCreatedDate(DateTime $createdDate)
	{
		$this->createdDate = $createdDate;
	}

	public function setType(string $type)
	{
		$this->type = $type;
	}

	public static function validateData(array &$data)
	{
		$valid = true;
		$errors = [];
		// validate the data
		if (empty($data['isbn'])) {
			$valid = false;
		}
		if (empty($data['title'])) {
			$valid = false;
		}
		if (empty($data['author'])) {
			$valid = false;
		}
		if (empty($data['createdDate'])) {
			$data['createdDate'] = new DateTime();
		} else if (!$data['createdDate'] instanceof DateTime) {
			$data['createdDate'] = new DateTime($data['createdDate']);
		}
		return $valid;
	}
}
