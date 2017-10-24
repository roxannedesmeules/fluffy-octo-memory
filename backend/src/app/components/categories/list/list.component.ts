import { Component, OnInit } from "@angular/core";
import { BreadcrumbItems } from "core/layout/breadcrumb/breadcrumb-items.model";
import { PageHeaderService } from "core/layout/page-header/page-header.service";
import { CategoriesService } from "services/categories/categories.service";

@Component({
	selector    : "app-categories-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit {

	header = {
		title      : "Categories",
		subTitle   : "List of categories",
		breadcrumb : [
			new BreadcrumbItems({ name : "Dashboard" }),
			new BreadcrumbItems({ name : "Categories", isActive : true }),
		],
	};

	constructor (private headerService: PageHeaderService, private categoriesService: CategoriesService) { }

	ngOnInit () {
		this.headerService.setPageHeader(this.header);

		this.categoriesService.findAll()
			.then((result: any) => {
				console.log(result);
			})
			.catch((error: any) => {});
	}

}
