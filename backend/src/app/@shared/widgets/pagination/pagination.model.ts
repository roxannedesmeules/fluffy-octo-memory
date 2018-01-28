import { Observable } from "rxjs/Observable";
import { Subject } from "rxjs/Subject";

export class Pagination {
	private _subject = new Subject<any>();

	public currentPage: number = 0;
	public pageCount: number;
	public perPage: number     = 10;
	public totalCount: number;

	constructor () {}

	public getData (): object {
		return {
			currentPage : this.currentPage,
			pageCount   : this.pageCount,
			perPage     : this.perPage,
			totalCount  : this.totalCount,
		};
	}

	public getService (): Observable<any> {
		return this._subject.asObservable();
	}

	public setPagination ( headers ) {
		this.totalCount  = Number(headers.get("X-Pagination-Total-Count"));
		this.pageCount   = Number(headers.get("X-Pagination-Page-Count"));
		this.currentPage = Number(headers.get("X-Pagination-Current-Page"));
		this.perPage     = Number(headers.get("X-Pagination-Per-Page"));
	}

	updateCurrentPage ( pageNumber: number ) {
		this.currentPage = (pageNumber - 1);

		this._subject.next(this.getData());
	}
}
