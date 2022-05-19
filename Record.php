<?php
require_once 'RecordInterface.php';

class Record implements RecordInterface
{
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
		if (!file_exists("/catalog/$this->type/$this->isbn") && unlink("/catalog/$this->type/$this->isbn")) {
			return true;
		}
		return false;
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
		$file = "/catalog/$this->type/$this->isbn";
		$fp = fopen($file, 'w+');
		fwrite($fp, "isbn=$this->isbn\n");
		fwrite($fp, "title=$this->title\n");
		fwrite($fp, "author=$this->author\n");
		fwrite($fp, "createdDate={$this->createdDate->format('Y-m-d H:i:s')}\n");
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
		if (!is_dir('/catalog')) {
			mkdir('/catalog');
		}
		$types = array_diff(scandir('/catalog'), ['.', '..']);
		if (empty($types)) {
			echo "No records found";
		}
		foreach ($types as $type) {
			$file = "/catalog/$type/$id";
			if (file_exists($file)) {
				$fp = fopen($file, 'r');
				$recordArray = [];
				foreach (explode("\n", fread($fp, filesize($file))) as $line) {
					$data = explode('=', $line);
					$recordArray[$data[0]] = $data[1];
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
		echo 'Validating data...';
		$valid = true;
		$errors = [];
		// validate the data
		if (empty($data['isbn'])) {
			$valid = false;
			echo 'ISBN is required';
		}
		if (empty($data['title'])) {
			$valid = false;
			echo 'Title is required';
		}
		if (empty($data['author'])) {
			$valid = false;
			echo 'Author is required';
		}
		if (empty($data['createdDate'])) {
			$data['createdDate'] = new DateTime();
		}
		return $valid;
	}
}
