import { Component, OnInit } from "@angular/core";
import { FormArray, FormBuilder, FormGroup, Validators } from "@angular/forms";
import { ActivatedRoute } from "@angular/router";
import { Category } from "@core/data/categories/category.model";
import { CategoryService } from "@core/data/categories/category.service";
import { ErrorResponse } from "@core/data/error-response.model";

import { Lang } from "@core/data/languages/lang.model";

@Component({
	selector    : "ngx-category-detail",
	templateUrl : "./detail.component.html",
	styleUrls   : [ "./detail.component.scss" ],
})
export class DetailComponent implements OnInit {

	public languages: Lang[] = [];

	public form: FormGroup;
	public errors: any = {};

	constructor ( private _route: ActivatedRoute,
				  private _builder: FormBuilder,
				  private service: CategoryService ) { }

	ngOnInit () {
		this.languages = this._route.snapshot.data[ "languages" ];

		this._createForm();
	}

	/**
	 * Create the Category form with a translation object for each languages available.
	 *
	 * @private
	 */
	private _createForm () {
		this.form = this._builder.group({
			is_active    : this._builder.control(0, [ Validators.required ]),
			translations : this._builder.array([]),
		});

		this.languages.forEach(( val ) => {
			const control = this._builder.group({
				lang_id : this._builder.control(val.id, [ Validators.required ]),
				name    : this._builder.control("", [ Validators.required ]),
				slug    : this._builder.control("", [ Validators.required ]),
			});

			this.getTranslations().push(control);
		});
	}

	public getErrors ( idx: number, field: string ) {
		if (!this.errors.hasOwnProperty("translations")) {
			return [];
		}

		if (!this.errors.translations.hasOwnProperty(idx)) {
			return [];
		}

		return this.errors.translations[ idx ][ field ];
	}

	/**
	 * Return the translations from the form as FormArray. (helper since the get is kinda long)
	 *
	 * @return {FormArray}
	 */
	public getTranslations (): FormArray {
		return this.form.get("translations") as FormArray;
	}

	/**
	 * Verify if the field passed was touched and is still invalid.
	 *
	 * @param {string} field
	 * @param {FormGroup} translation
	 *
	 * @return {boolean}
	 */
	public showError ( field: string, translation?: FormGroup ): boolean {
		if (translation) {
			return (translation.get(field).touched && translation.get(field).invalid);
		}

		return (this.form.get(field).touched && this.form.get(field).invalid);
	}

	/**
	 *
	 */
	public save () {
		this.errors = [];

		let body = new Category();
		body     = body.form(this.form.getRawValue());

		this.service
				.create(body)
				.then(( result: any ) => {
					console.log(result);
				})
				.catch(( error: ErrorResponse ) => {
					this.errors = error.form_error;
				});
	}

}
