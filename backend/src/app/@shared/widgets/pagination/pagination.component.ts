import { Component, Input, OnInit } from "@angular/core";
import { Pagination } from "@shared/widgets/pagination/pagination.model";

@Component({
	selector    : "ngx-pagination",
	templateUrl : "./pagination.component.html",
	styleUrls   : [ "./pagination.component.scss" ],
})
export class PaginationComponent implements OnInit {

	MAX_PAGE = 5;

	public current: number;

	@Input() pagination: Pagination;

	constructor () { }

	ngOnInit () {
		this.current = this.pagination.currentPage;
	}

	pageNumbers (): number[] {
		const pages = Array(this.pagination.pageCount).fill(1).map(( x, i ) => (i + 1));

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

	public goToFirst () {
		if (this.pagination.currentPage !== 1) {
			this.goToPage(1);
		}
	}

	public goToPrevious () {
		if (this.pagination.currentPage > 1) {
			this.goToPage((this.pagination.currentPage - 1));
		}
	}

	public goToPage ( pageNumber: number ) {
		this.current = pageNumber;

		this.pagination.updateCurrentPage(pageNumber);
	}

	public goToNext () {
		if (this.pagination.currentPage < this.pagination.pageCount) {
			this.goToPage((this.pagination.currentPage + 1));
		}
	}

	public goToLast () {
		if (this.pagination.currentPage !== this.pagination.pageCount) {
			this.goToPage(this.pagination.pageCount);
		}
	}
}
