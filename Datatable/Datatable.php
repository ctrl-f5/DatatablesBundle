<?php
declare(strict_types = 1);
namespace phpro\DatatablesBundle\Datatable;

use phpro\DatatablesBundle\Column\Column;
use phpro\DatatablesBundle\Column\ColumnInterface;
use phpro\DatatablesBundle\DataExtractor\DataExtractorInterface;
use phpro\DatatablesBundle\Request\RequestInterface;
use phpro\DatatablesBundle\Response\Response;

/**
 * Class Datatable
 *
 * @package phpro\DatatablesBundle\Datatable
 */
class Datatable implements DatatableInterface
{
    /**
     * @var DataExtractorInterface
     */
    private $extractor;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var array|ColumnInterface[]
     */
    private $columns;

    /**
     * Datatable constructor.
     *
     * @param string $alias
     * @param DataExtractorInterface $extractor
     */
    public function __construct($alias, DataExtractorInterface $extractor)
    {
        $this->extractor = $extractor;
        $this->alias = $alias;
    }

    /**
     * Generates the correct API-response for the DataTable,
     * should be indulged into a JSONResponse Object by the controller
     *
     * @param RequestInterface $request
     * @return Response
     */
    public function buildResponse(RequestInterface $request) : Response
    {
        $source = $this->extractor->extract($request);
        $data = [];

        foreach ($source as $target) {
            $row = [];
            foreach ($this->columns as $column) {
                $row[] = $column->extractValue($target);
            }
            $data[] = $row;
        }

        return new Response($data);
    }

    /**
     * Adds a Column element to the DataTable's columns
     *
     * @param ColumnInterface $column
     * @return DataTableInterface
     */
    public function addColumn(ColumnInterface $column) : DatatableInterface
    {
        $this->columns[$column->getName()] = $column;
        return $this;
    }

    /**
     * Creates a Column element and adds it tho the DataTable's columns
     *
     * @param string $name
     * @param array $options
     * @return DataTableInterface
     */
    public function createColumn(string $name, array $options = []) : DatatableInterface
    {
        return $this->addColumn(new Column($name, $options));
    }

    /**
     * Returns all columns that are present in the table
     *
     * @return array|ColumnInterface[]
     */
    public function getColumns() : array
    {
        return $this->columns;
    }

    /**
     * Returns the alias of the table for which it is registered in the manager
     *
     * @return string
     */
    public function getAlias() : string
    {
        return $this->alias;
    }
}