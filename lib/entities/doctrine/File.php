<?php

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Entity
 * @Table(name="file")
 */
class File extends \sfDoctrineActiveEntity
{
  /**
   * @var integer $id
   *
   * @Column(type="integer", length=4)
   * @Id
   * @GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  /**
   * @var string $name
   *
   * @Column(type="string", length=64)
   */
  private $name;

  /**
   * @var string $type
   *
   * @Column(type="string", length=64)
   */
  private $type;

  /**
   * @var integer $filesize
   *
   * @Column(type="integer", length=4)
   */
  private $filesize;

  /**
   * @var string $original_filename
   *
   * @Column(type="string", nullable=true)
   */
  private $original_filename;

  /**
   * @var datetime $created_at
   *
   * @Column(type="datetime")
   * @Gedmo\Timestampable(on="create")
   */
  private $created_at;

  /**
   * @var datetime $updated_at
   *
   * @Column(type="datetime")
   * @Gedmo\Timestampable(on="update")
   */
  private $updated_at;

  /**
   * @OneToOne(targetEntity="FileBin", mappedBy="file", cascade={"persist", "remove"})
   */
  private $file_bin;


  public function getType()
  {
    return $this->type;
  }

  public function getFileBin()
  {
    return $this->file_bin;
  }

  public function __toString()
  {
    return (string)$this->name;
  }

  public function isImage()
  {
    $type = $this->type;
    if ($type === 'image/jpeg'
      || $type === 'image/gif'
      || $type === 'image/png')
    {
      return true;
    }

    return false;
  }

  public function getImageFormat()
  {
    if (!$this->isImage())
    {
      return false;
    }

    $type = explode('/', $this->type);
    $result = $type[1];

    if ($result === 'jpeg')
    {
      $result = 'jpg';
    }

    return $result;
  }
}
