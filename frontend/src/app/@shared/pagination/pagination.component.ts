import { Component, Input, OnInit } from "@angular/core";
import { Pagination } from "./pagination.model";

@Component({
    selector    : "app-pagination",
    templateUrl : "./pagination.component.html",
    styleUrls   : [ "./pagination.component.scss" ],
})
export class PaginationComponent implements OnInit {

    MAX_PAGE = 5;

    public current: number;

    @Input() pagination: Pagination;

    constructor() {
    }

    ngOnInit() {
    }

    pageNumbers(): number[] {
        const pages = Array(this.pagination.pageCount).fill(1).map((x, i) => (i + 1));

        if (this.pagination.pageCount <= this.MAX_PAGE) {
            return pages;
        } else if (this.pagination.isFirst() || this.pagination.isPage(2)) {
            return pages.slice(0, this.MAX_PAGE);
        } else if (this.pagination.isLast() || this.pagination.isPage(this.pagination.pageCount - 1)) {
            return pages.slice(this.pagination.pageCount - this.MAX_PAGE, this.pagination.pageCount);
        } else {
            return pages.slice(this.pagination.currentPage - 3, this.pagination.currentPage + 2);
        }
    }

    /**
     * If not the first page (to avoid page reload for no reason), go to page number 1.
     */
    public goToFirst() {
        if (!this.pagination.isFirst()) {
            this.goToPage(1);
        }
    }

    /**
     * If not the first page, go to the page before this one.
     */
    public goToPrevious() {
        if (!this.pagination.isFirst()) {
            this.goToPage((this.pagination.currentPage - 1));
        }
    }

    /**
     * Update the pagination object with a specific page number and also update the current property so the right page
     * number is selected.
     *
     * @param {number} pageNumber
     */
    public goToPage(pageNumber: number) {
        this.pagination.currentPage = pageNumber;

        this.pagination.updateCurrentPage(pageNumber);
    }

    /**
     * If not the last page, go to the next page.
     */
    public goToNext() {
        if (!this.pagination.isLast()) {
            this.goToPage((this.pagination.currentPage + 1));
        }
    }

    /**
     * If not the last page (to avoid page reload for no reason), go to the last page number.
     */
    public goToLast() {
        if (!this.pagination.isLast()) {
            this.goToPage(this.pagination.pageCount);
        }
    }

    public isCurrent(page: number): boolean {
        return this.pagination.currentPage === page;
    }
}
