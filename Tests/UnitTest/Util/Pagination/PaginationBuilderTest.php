<?php

namespace Visca\Bundle\CoreBundle\Tests\UnitTest\Util\Pagination;

use PHPUnit_Framework_TestCase;
use Visca\Bundle\CoreBundle\Util\Pagination\PaginationBuilder;

/**
 * Class PaginationBuilderTest.
 */
class PaginationBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [5, 1, 30, 100, 100, 1, 4, null, 2],
        ];
    }

    /**
     * Test the pagination data.
     *
     * @param int $range             Number of page to display
     * @param int $currentPageNumber Current page
     * @param int $numItemsPerPage   Number of items per page displayed
     * @param int $totalCount        Total of items
     * @param int $itemsCount
     * @param int $expectedStartPage
     * @param int $expectedEndPage
     * @param int $expectedPrevious
     * @param int $expectedNext
     *
     * @dataProvider dataProvider
     */
    public function testPaginationData(
        $range,
        $currentPageNumber,
        $numItemsPerPage,
        $totalCount,
        $itemsCount,
        $expectedStartPage,
        $expectedEndPage,
        $expectedPrevious,
        $expectedNext
    ) {
        $pagination = new PaginationBuilder(
            $range,
            $currentPageNumber,
            $numItemsPerPage,
            $totalCount,
            $itemsCount
        );
        $paginationData = $pagination->getPaginationData();

        $this->assertEquals($expectedStartPage, $paginationData['startPage']);
        $this->assertEquals($expectedEndPage, $paginationData['endPage']);
        $this->assertEquals($expectedPrevious, $paginationData['previous']);
        $this->assertEquals($expectedNext, $paginationData['next']);
    }
}
