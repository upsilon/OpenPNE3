<?php

/**
 * @Entity
 * @Table(name="file_bin")
 */
class FileBin extends \sfDoctrineActiveEntity
{
  /**
   * @var integer $file_id
   *
   * @Column(type="integer", length=4)
   * @Id
   * @GeneratedValue(strategy="NONE")
   */
  private $file_id;

  /**
   * @var string $bin
   *
   * @Column(type="text")
   */
  private $bin;

  /**
   * @OneToOne(targetEntity="File", inversedBy="file_bin", cascade={"persist","remove"})
   * @JoinColumn(name="file_id", referencedColumnName="id")
   */
  private $file;

  public function getBin()
  {
    return $this->bin;
  }
}
