import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { Category } from "@core/data/categories/category.model";

@Component({
	selector    : "ngx-category-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit {

	public list: Category[];

	constructor ( private _route: ActivatedRoute ) { }

	ngOnInit () {
		this.list = this._route.snapshot.data[ "list" ];
	}

}
