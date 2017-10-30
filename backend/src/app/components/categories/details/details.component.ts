import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { FormArray, FormBuilder, FormGroup, Validators } from "@angular/forms";

import { BreadcrumbItems } from "core/layout/breadcrumb/breadcrumb-items.model";
import { PageHeaderService } from "core/layout/page-header/page-header.service";

import { Category } from "models/categories/category.model";
import { Lang } from "models/lang/lang.model";

@Component({
	selector    : "app-categories-details",
	templateUrl : "./details.component.html",
	styleUrls   : [ "./details.component.scss" ],
})
export class DetailsComponent implements OnInit {
	public category: Category = new Category();
	public languages: Lang[]  = [];
	public form: FormGroup;

	header = {
		title      : "Categories",
		subTitle   : "Create a category",
		breadcrumb : [
			new BreadcrumbItems({ name : "Categories", link : "/categories" }),
			new BreadcrumbItems({ name : "Create", isActive : true }),
		],
	};

	constructor (private _route: ActivatedRoute,
	             private _builder: FormBuilder,
	             private headerService: PageHeaderService) { }

	ngOnInit () {
		this.headerService.setPageHeader(this.header);

		this.languages = this._route.snapshot.data[ "languages" ];

		this._initForm();
	}

	private _initForm () {
		this.form = this._builder.group({
			is_active    : this._builder.control(0, [ Validators.required ]),
			translations : this._builder.array([])
		});

		this.languages.forEach((val, idx) => {
			let control = this._builder.group({
				lang : this._builder.control(val.icu, [ Validators.required ]),
				name : this._builder.control('', [ Validators.required ]),
				slug : this._builder.control('', [ Validators.required ]),
			});

			this.getTranslations().push(control);
		});
	}

	public getTranslations (): FormArray {
		return this.form.get("translations") as FormArray;
	}

	public save () {

	}
}
