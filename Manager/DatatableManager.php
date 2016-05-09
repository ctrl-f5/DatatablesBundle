<?php
declare(strict_types = 1);
namespace phpro\DatatablesBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use phpro\DatatablesBundle\Datatable\DatatableInterface;
use phpro\DatatablesBundle\Exception\DatatableNotFoundException;
use phpro\DatatablesBundle\Exception\RuntimeException;

/**
 * Class DatatableManager
 * DatatableManager holds all the Datatables
 * 
 * @package phpro\DatatablesBundle\Manager
 */
class DatatableManager implements DatatableManagerInterface
{
    /**
     * @var ArrayCollection|DatatableInterface[]
     */
    private $datatables;

    /**
     * DatatableManager constructor.
     */
    public function __construct()
    {
        $this->datatables = new ArrayCollection();
    }

    /**
     * Checks if the manager has a Datatable with given alias
     *
     * @param $alias
     * @return bool
     */
    public function has($alias) : bool
    {
        return $this->datatables->get($alias) instanceof DatatableInterface;
    }

    /**
     * Returns the Datatable registered with the given alias
     *
     * @param $alias
     * @return DatatableInterface
     * @throws DatatableNotFoundException
     */
    public function get($alias) : DatatableInterface
    {
        if (true !== $this->has($alias)) {
            throw DatatableNotFoundException::fromAlias($alias);
        }

        return $this->datatables->get($alias);
    }

    /**
     * Registers a datatable under its current alias
     *
     * @param DatatableInterface $datatable
     * @throws RuntimeException
     */
    public function add(DatatableInterface $datatable) : void
    {
        if (true === $this->has($datatable->getAlias())) {
            throw new RuntimeException(
                "Datatable {$datatable->getAlias()} is already registered, cannot register a table twice"
            );
        }

        $this->datatables->set($datatable->getAlias(), $datatable);
    }
}
