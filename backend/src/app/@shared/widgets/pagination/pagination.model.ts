import { Observable } from "rxjs/Observable";
import { Subject } from "rxjs/Subject";
import { HttpHeaders } from "@angular/common/http";

/**
 * @class Pagination
 * Manages the information related to paging a list. Currently only method supported is by HttpHeaders.
 *
 * @property {number} currentPage current page number - 0 based
 * @property {number} pageCount number of pages for the current list
 * @property {number} perPage number of items displayed per page
 * @property {number} totalCount total number of items in the non-paged list
 */
export class Pagination {
	private _subject = new Subject<any>();

	public currentPage: number = 0;
	public pageCount: number;
	public perPage: number     = 10;
	public totalCount: number;

	constructor () {}

	/**
	 * Return the model's information as a plain object.
	 *
	 * @returns {object}
	 */
	public getData (): object {
		return {
			currentPage: this.currentPage,
			pageCount  : this.pageCount,
			perPage    : this.perPage,
			totalCount : this.totalCount,
		};
	}

	/**
	 * Returns the private _subject property as an Observable so any changes made to the model can easily be
	 * communicated to the component with the list.
	 *
	 * @returns {Observable<any>}
	 */
	public getService (): Observable<any> {
		return this._subject.asObservable();
	}

	/**
	 * Verify if the current page is the first one.
	 *
	 * @returns {boolean}
	 */
	public isFirst (): boolean {
		return this.isPage(1);
	}

	/**
	 * Verify if the current page is the last.
	 *
	 * @returns {boolean}
	 */
	public isLast (): boolean {
		return this.isPage(this.pageCount);
	}

	/**
	 * Helper method to know if the current page is a specific page number
	 *
	 * @param {number} pageNumber
	 *
	 * @returns {boolean}
	 */
	public isPage (pageNumber: number): boolean {
		return (this.currentPage === pageNumber);
	}

	/**
	 * Defines the model information from the header object passed in parameter.
	 *
	 * @param {HttpHeaders} headers
	 */
	public setPagination ( headers: HttpHeaders ) {
		this.totalCount  = Number( headers.get( "X-Pagination-Total-Count" ) );
		this.pageCount   = Number( headers.get( "X-Pagination-Page-Count" ) );
		this.currentPage = Number( headers.get( "X-Pagination-Current-Page" ) );
		this.perPage     = Number( headers.get( "X-Pagination-Per-Page" ) );
	}

	/**
	 * Called to update the current page number. 1 is removed from the number since the pagination for any backend
	 * purpose is 0 based while the numbers displayed start at 1.
	 *
	 * @param {number} pageNumber
	 */
	public updateCurrentPage ( pageNumber: number ) {
		this.currentPage = (pageNumber - 1);

		this._subject.next( this.getData() );
	}
}
