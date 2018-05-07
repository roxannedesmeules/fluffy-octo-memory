import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { FormArray, FormBuilder, FormGroup, Validators } from "@angular/forms";

import { Category, CategoryService } from "@core/data/categories";
import { Lang } from "@core/data/languages";
import { LoggerService } from "@shared/logger/logger.service";
import { ErrorResponse } from "@core/data/error-response.model";
import { AtIndexOfPipe } from "@shared/pipes/array/at-index-of.pipe";
import { SlugPipe } from "@shared/pipes/string/slug.pipe";

@Component({
	selector    : "app-category-detail",
	templateUrl : "./detail.component.html",
	styleUrls   : [ "./detail.component.scss" ],
})
export class DetailComponent implements OnInit {

	public title = "New category";

	public category: Category;
	public languages: Lang[];

	public form: FormGroup;
	public errors: any = {};
	public loading       = false;
	public statusLoading = false;

	constructor (private _route: ActivatedRoute,
				 private _builder: FormBuilder,
				 private atIndexOf: AtIndexOfPipe,
				 private slugPipe: SlugPipe,
				 private service: CategoryService,
				 private logger: LoggerService) {
	}

	ngOnInit () {
		this._setData();
		this._createForm();

		if (!this.isCreate()) {
			this.title = "Update category";
		}
	}

	/**
	 * Create the Category form with a translation object for each languages available.
	 *
	 * @private
	 */
	private _createForm () {
		this.form = this._builder.group({
			is_active    : this._builder.control(this.category.is_active, [ Validators.required ]),
			translations : this._builder.array([]),
		});

		this.languages.forEach(( val ) => {
			const translation = this.category.findTranslation(val.icu);
			const control     = this._builder.group({
				lang_id : this._builder.control(val.id, [ Validators.required ]),
				name    : this._builder.control(translation.name, [ Validators.required ]),
				slug    : this._builder.control(translation.slug, [ Validators.required ]),
			});

			control.get("slug").disable();

			this.getTranslations().push(control);
		});
	}

	public displayButton ( action: string ): boolean {
		if (this.isCreate()) {
			return false;
		}

		switch (action) {
			case 'activate':
				return this.category.isInactive();

			case 'deactivate' :
				// no break;
			default :
				return this.category.isActive();
		}
	}

	/**
	 *
	 * @param {number} langId
	 * @param {string} field
	 * @return {any}
	 */
	public getErrors ( langId: number, field: string ) {
		const langIcu = this.atIndexOf.transform(langId, this.languages, "id", "icu");

		if (!this.errors.hasOwnProperty(langIcu)) {
			return [];
		}

		return this.errors[ langIcu ][ 0 ][ field ];
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
	 * Verify if the current page is the creation page.
	 *
	 * @return {boolean}
	 */
	public isCreate () {
		return (typeof this.category === "undefined" || typeof this.category.id === "undefined");
	}

	/**
	 * Reset the form to all empty values, so another category can easily be created.
	 */
	public resetForm () {
		this.form.get("is_active").setValue(this.category.is_active);

		this.languages.forEach(( val, idx ) => {
			const translation = this.category.findTranslation(val.icu);

			this.getTranslations().at(idx).get("lang_id").setValue(val.id);
			this.getTranslations().at(idx).get("name").setValue(translation.name);
			this.getTranslations().at(idx).get("slug").setValue(translation.slug);
			this.getTranslations().at(idx).get("slug").disable();
		});
	}

	/**
	 *
	 */
	public save () {
		this.errors  = [];
		this.loading = true;

		let req  = null;
		let msg  = "Changes to category where correctly saved";
		let body = this.category.form(this.form.getRawValue());

		if (this.isCreate()) {
			req = this.service.create(body);
			msg = "A new category was successfully created";
		} else {
			req = this.service.update(this.category.id, body);
			msg = `Category #${this.category.id} was successfully updated`;
		}

		req.subscribe(
				(result: Category) => {
					this.loading  = false;

					this.logger.success(msg);

					if (this.isCreate()) {
						this.resetForm();
					} else {
						this.category = result;
					}
				},
				(error: ErrorResponse) => {
					this.loading = false;
					this.errors  = error.form_error;

					this.logger.error(error.shortMessage);
				}
		);
	}

	/**
	 * Get the data resolved by the route, then assign it to the right property.
	 *
	 * @private
	 */
	private _setData () {
		const routeLanguages = this._route.snapshot.data[ "languages" ];
		const routeCategory  = this._route.snapshot.data[ "category" ];

		this.languages = (routeLanguages) ? routeLanguages : [];
		this.category  = (routeCategory) ? routeCategory : new Category();
	}

	/**
	 *
	 * @param {number} translationIdx
	 */
	public setSlug ( translationIdx: number ) {
		//  get the current name
		const name = this.getTranslations().at(translationIdx).get("name").value;

		//  transform the name to remove spaces, apostrophe and transform accents
		const slug = this.slugPipe.transform(name);

		this.getTranslations().at(translationIdx).get("slug").setValue(slug);
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
			const langId = translation.get("lang_id").value;

			return ((translation.get(field).touched && translation.get(field).invalid) || this.getErrors(langId, field).length > 0);
		}

		return (this.form.get(field).touched && this.form.get(field).invalid);
	}

	public updateActiveStatus () {
		switch (this.category.is_active) {
			case 1 :
				this.form.get("is_active").setValue(0);
				break;
			case 0 :
				this.form.get("is_active").setValue(1);
				break;
		}

		this.errors        = [];
		this.statusLoading = true;

		let msg = `Category #${this.category.id} was successfully updated`;
		let body = this.category.form(this.form.getRawValue());

		this.service
			.update(this.category.id, body)
			.subscribe(
				(result: Category) => {
					this.category = result;
					this.statusLoading  = false;

					this.logger.success(msg);
				},
				(error: ErrorResponse) => {
					this.statusLoading = false;
					this.errors  = error.form_error;

					this.logger.error(error.shortMessage);
				}
			);
	}
}
