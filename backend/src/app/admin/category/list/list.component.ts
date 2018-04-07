import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { Category } from "@core/data/categories";
import { ErrorResponse } from "@core/data/error-response.model";
import { Lang } from "@core/data/languages";
import { CategoryService } from "@core/data/categories";
import { LoggerService } from "@shared/logger/logger.service";

@Component({
	selector    : "app-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit {

	public categories: Category[] = [];
	public languages: Lang[] = [];

	constructor ( private _route: ActivatedRoute,
				  private service: CategoryService,
				  private logger: LoggerService ) {}

	ngOnInit () {
		this.categories = this._route.snapshot.data[ "list" ];
		this.languages  = this._route.snapshot.data[ "languages" ];
	}

	/**
	 * Call service to make API request and delete the record associated to the category ID passed in parameter.
	 *
	 * @param categoryId
	 */
	deleteOne ( categoryId ) {
		this.service
			.delete( categoryId )
			.subscribe(
					() => {
						this.logger.success("Category successfully deleted");

						this.updateList();
					},
					(err: ErrorResponse) => {
						this.logger.error(err.shortMessage);
					}
			);
	}

	updateList () {
		this.service
			.findAll()
			.subscribe(
				(result: Category[]) => {
					this.categories = result;
				},
				(err: ErrorResponse) => {}
			);
	}
}
