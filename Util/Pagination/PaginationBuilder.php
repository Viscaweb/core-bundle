<?php

namespace Visca\Bundle\CoreBundle\Util\Pagination;

/**
 * Class PaginationBuilder.
 */
class PaginationBuilder
{
    /**
     * @var int
     */
    private $pageRange = 5;

    /**
     * @var int
     */
    private $currentPageNumber;

    /**
     * @var int
     */
    private $numItemsPerPage;

    /**
     * @var int
     */
    private $itemsCount;

    /**
     * @var int
     */
    private $totalCount;

    /**
     * @param int $range             Number of page to display
     * @param int $currentPageNumber Current page
     * @param int $numItemsPerPage   Number of items per page displayed
     * @param int $totalCount        Total of items
     * @param int $itemsCount
     */
    public function __construct(
        $range,
        $currentPageNumber,
        $numItemsPerPage,
        $totalCount,
        $itemsCount
    ) {
        $this->pageRange = abs(intval($range));
        $this->currentPageNumber = $currentPageNumber;
        $this->numItemsPerPage = $numItemsPerPage;
        $this->totalCount = $totalCount;
        $this->itemsCount = $itemsCount;
    }

    /**
     * @return array
     */
    public function getPaginationData()
    {
        $pageCount = $this->getPageCount();
        $current = $this->currentPageNumber;

        if ($pageCount < $current) {
            $this->currentPageNumber = $current = $pageCount;
        }

        if ($this->pageRange > $pageCount) {
            $this->pageRange = $pageCount;
        }

        $delta = ceil($this->pageRange / 2);

        if ($current - $delta > $pageCount - $this->pageRange) {
            $pages = range($pageCount - $this->pageRange + 1, $pageCount);
        } else {
            if ($current - $delta < 0) {
                $delta = $current;
            }

            $offset = $current - $delta;
            $pages = range($offset + 1, $offset + $this->pageRange);
        }

        $proximity = floor($this->pageRange / 2);

        $startPage = $current - $proximity;
        $endPage = $current + $proximity;

        if ($startPage < 1) {
            $endPage = min($endPage + (1 - $startPage), $pageCount);
            $startPage = 1;
        }

        if ($endPage > $pageCount) {
            $startPage = max($startPage - ($endPage - $pageCount), 1);
            $endPage = $pageCount;
        }

        $viewData = [
            'last' => $pageCount,
            'current' => $current,
            'numItemsPerPage' => $this->numItemsPerPage,
            'first' => 1,
            'pageCount' => $pageCount,
            'totalCount' => $this->totalCount,
            'pageRange' => $this->pageRange,
            'startPage' => $startPage,
            'endPage' => $endPage,
        ];

        if ($current - 1 > 0) {
            $viewData['previous'] = $current - 1;
        } else {
            $viewData['previous'] = null;
        }

        if ($current + 1 <= $pageCount) {
            $viewData['next'] = $current + 1;
        } else {
            $viewData['next'] = null;
        }

        $viewData['pagesInRange'] = $pages;
        $viewData['firstPageInRange'] = min($pages);
        $viewData['lastPageInRange'] = max($pages);

        $viewData['currentItemCount'] = $this->getItemsCount();
        $viewData['firstItemNumber'] = (($current - 1) * $this->numItemsPerPage) + 1;
        $viewData['lastItemNumber'] = $viewData['firstItemNumber'] + $viewData['currentItemCount'] - 1;

        return $viewData;
    }

    /**
     * The total number of pages.
     *
     * @return int
     */
    public function getPageCount()
    {
        return intval(ceil($this->totalCount / $this->numItemsPerPage));
    }

    /**
     * @return int
     */
    public function getItemsCount()
    {
        return $this->itemsCount;
    }

    /**
     * @return int
     */
    public function getPageRange()
    {
        return $this->pageRange;
    }

    /**
     * @return mixed
     */
    public function getCurrentPageNumber()
    {
        return $this->currentPageNumber;
    }

    /**
     * @return mixed
     */
    public function getNumItemsPerPage()
    {
        return $this->numItemsPerPage;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }
}
