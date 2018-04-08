import { Component, OnInit } from "@angular/core";
import { FormArray, FormBuilder, FormGroup, Validators } from "@angular/forms";
import { ActivatedRoute } from "@angular/router";
import { LoggerService } from "@shared/logger/logger.service";
import { AtIndexOfPipe } from "@shared/pipes/array/at-index-of.pipe";
import { SlugPipe } from "@shared/pipes/string/slug.pipe";

import { Tag, TagService } from "@core/data/tags";
import { Lang } from "@core/data/languages";
import { ErrorResponse } from "@core/data/error-response.model";

@Component({
	selector    : "app-detail",
	templateUrl : "./detail.component.html",
	styleUrls   : [ "./detail.component.scss" ],
})
export class DetailComponent implements OnInit {

	public title = "New tag";

	public tag: Tag;
	public languages: Lang[];

	public form: FormGroup;
	public errors: any = {};
	public loading     = false;

	constructor (private _route: ActivatedRoute,
				 private _builder: FormBuilder,
				 private atIndexOf: AtIndexOfPipe,
				 private slugPipe: SlugPipe,
				 private service: TagService,
				 private logger: LoggerService) {}

	ngOnInit () {
		this._setData();
		this._createForm();

		if (!this.isCreate()) {
			this.title = "Update tag";
		}
	}

	private _createForm () {
		this.form = this._builder.group({
			translations : this._builder.array([]),
		});

		this.languages.forEach((val) => {
			const translation = this.tag.findTranslation(val.icu);
			const control     = this._builder.group({
				lang_id : this._builder.control(val.id, [ Validators.required ]),
				name    : this._builder.control(translation.name, [ Validators.required ]),
				slug    : this._builder.control(translation.slug, [ Validators.required ]),
			});

			control.get("slug").disable();

			this.getTranslations().push(control);
		});
	}

	/**
	 *
	 * @param {number} idx
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
		return (typeof this.tag === "undefined" || typeof this.tag.id === "undefined");
	}

	/**
	 * Reset the form to all empty values, so another category can easily be created.
	 */
	public resetForm () {
		this.languages.forEach(( val, idx ) => {
			this.getTranslations().at(idx).reset();
			this.getTranslations().at(idx).get("lang_id").setValue(val.id);
		});
	}

	/**
	 *
	 */
	public save () {
		this.errors  = [];
		this.loading = true;

		let req  = null;
		let msg  = "Changes to tag where correctly saved";
		let body = new Tag();
		body     = body.form(this.form.getRawValue());

		if (this.isCreate()) {
			req = this.service.create(body);
			msg = "A new tag was successfully created";
		} else {
			req = this.service.update(this.tag.id, body);
			msg = `Tag #${this.tag.id} was successfully updated`;
		}

		req.subscribe(
				(result: Tag) => {
					this.loading = false;

					this.logger.success(msg);

					if (this.isCreate()) {
						this.resetForm();
					}
				},
				(error: ErrorResponse) => {
					this.loading = false;
					this.errors  = error.form_error;

					this.logger.error(error.shortMessage);
				}
		);
	}

	private _setData () {
		const routeLanguages = this._route.snapshot.data[ "languages" ];
		const routeTag       = this._route.snapshot.data[ "tag" ];

		this.tag       = routeTag || new Tag();
		this.languages = routeLanguages || [];
	}

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
	 * @param {number} langId
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
}
