import { Component, OnInit } from "@angular/core";
import { FormArray, FormBuilder, FormGroup, Validators } from "@angular/forms";
import { ActivatedRoute } from "@angular/router";
import { ToasterService } from "angular2-toaster";

import { AtIndexOfPipe } from "@shared/pipes/array/at-index-of.pipe";
import { SlugPipe } from "@shared/pipes/string/slug.pipe";

import { Post } from "@core/data/posts/post.model";
import { findStatusById, PostStatus } from "@core/data/posts/post-status.model";
import { PostService } from "@core/data/posts/post.service";
import { Category } from "@core/data/categories/category.model";
import { Lang } from "@core/data/languages/lang.model";
import { ErrorResponse } from "@core/data/error-response.model";

import "./ckeditor.loader";
import "ckeditor";

@Component({
	selector    : "ngx-post-detail",
	templateUrl : "./detail.component.html",
	styleUrls   : [ "./detail.component.scss" ],
})
export class DetailComponent implements OnInit {

	public post: Post;
	public categories: Category[];
	public languages: Lang[];
	public statuses: PostStatus[];

	public title    = "Posts";
	public subtitle = "Create a post";

	public form: FormGroup;

	public errors: any = {};
	public submitted   = false;

	constructor ( private _route: ActivatedRoute,
				  private _builder: FormBuilder,
				  private atIndexOfPipe: AtIndexOfPipe,
				  private slugPipe: SlugPipe,
				  private toastService: ToasterService,
				  private service: PostService ) { }

	ngOnInit () {
		this._setData();
		this._createForm();

		if (!this.isCreate()) {
			this.subtitle = `Update post #${this.post.id}`;
		}
	}

	public canMoveTo ( newStatus: string ): boolean {
		//  find the status
		const status = findStatusById(this.statuses, this.post.post_status_id);

		//  return if the status can be moved
		return status.canMoveToStatus(newStatus);
	}

	public changeSlug ( translationIdx: number ) {
		const title = this.getTranslations().at(translationIdx).get("title").value;
		const slug  = this.slugPipe.transform(title);

		this.getTranslations().at(translationIdx).get("slug").setValue(slug);
	}

	/**
	 * Create the reactive form.
	 *
	 * @private
	 */
	private _createForm () {
		let status = this.post.post_status_id;

		if (this.isCreate()) {
			status = this.atIndexOfPipe.transform("draft", this.statuses, "name", "id");
		}

		this.form = this._builder.group({
			category_id    : this._builder.control(this.post.category_id, [ Validators.required ]),
			post_status_id : this._builder.control(status, [ Validators.required ]),
			translations   : this._builder.array([]),
		});

		this.languages.forEach(( val, idx ) => {
			const translation = this.post.findTranslation(val.icu);
			const control     = this._builder.group({
				lang_id : this._builder.control(val.id),
				title   : this._builder.control(translation.title, [ Validators.required ]),
				slug    : this._builder.control(translation.slug, [ Validators.required ]),
				content : this._builder.control(translation.content, [ Validators.required ]),
			});

			this.getTranslations().push(control);
		});
	}

	/**
	 *
	 * @param {string} field
	 * @param {number} idx
	 *
	 * @return {any[]}
	 */
	public getErrors ( field: string, idx?: number ): any[] {
		let error = [];

		if (typeof idx !== "undefined") {
			error = (this.errors.hasOwnProperty("translations") && this.errors.translations.hasOwnProperty(idx)) ?
					this.errors.translations[ idx ][ field ] : [];
		} else {
			error = (this.errors.hasOwnProperty(field)) ? this.errors[ field ] : [];
		}

		return error;
	}

	/**
	 * Shorthand method to easily get all translations from the form object.
	 *
	 * @return {FormArray}
	 */
	public getTranslations (): FormArray {
		if (this.form.get("translations")) {
			return this.form.get("translations") as FormArray;
		}

		return this._builder.array([]);
	}

	/**
	 *
	 * @param {string} field
	 * @param {number} idx
	 *
	 * @return {boolean}
	 */
	public hasErrors ( field: string, idx?: number ): boolean {
		let control = this.form.get(field);
		let errors  = (this.errors.hasOwnProperty(field)) ? this.errors[ field ] : [];

		if (typeof idx !== "undefined") {
			control = this.getTranslations().at(idx).get(field);
			errors  = (this.errors.hasOwnProperty("translations") && this.errors.translations.hasOwnProperty(idx)) ?
					this.errors.translations[ idx ][ field ] : [];
		}

		return ((this.submitted || control.touched) && (control.invalid || errors.length > 0));
	}

	/**
	 * Check if the current page load is for the create form. It will return false if it's for the update form.
	 *
	 * @return {boolean}
	 */
	public isCreate () {
		return (typeof this.post === "undefined" || typeof this.post.id === "undefined");
	}

	/**
	 * This method will reset the form to initial values.
	 *
	 * @private
	 */
	private _resetForm () {
		this.form.get("category_id").reset();

		this.languages.forEach(( val, idx ) => {
			this.getTranslations().at(idx).reset();
			this.getTranslations().at(idx).get("lang_id").setValue(val.id);
		});
	}

	/**
	 *
	 */
	public save () {
		this.submitted = true;

		let request = null;
		let message = "";
		let body    = new Post();
		body        = body.form(this.form.getRawValue());

		if (this.isCreate()) {
			request = this.service.create(body);
			message = "A new post was successfully created";
		} else {
			request = this.service.update(this.post.id, body);
			message = `Post #${this.post.id} was successfully updated`;
		}

		request
				.then(( result: any ) => {
					this.submitted = false;
					this.toastService.popAsync("success", "Yeah!", message);

					if (this.isCreate()) {
						this._resetForm();
					}
				})
				.catch(( error: ErrorResponse ) => {
					this.errors = error.form_error;

					this.toastService.popAsync("error", "Please try again...", "Check the form to correct these errors.");
				});
	}

	public saveStatusChange ( status: string ) {
		//  update the status change
		const statusId = this.atIndexOfPipe.transform(status, this.statuses, "name", "id");

		this.post.post_status_id = statusId;
		this.form.get("post_status_id").setValue(statusId);

		//  save changes
		this.save();
	}

	/**
	 * Will set the data from the route, if there is a need, a default value will be assigned.
	 *
	 * @private
	 */
	private _setData () {
		this.languages  = this._route.snapshot.data[ "languages" ];
		this.categories = this._route.snapshot.data[ "categories" ];
		this.statuses   = this._route.snapshot.data[ "statuses" ];

		const routePost = this._route.snapshot.data[ "post" ];
		this.post       = (routePost) ? routePost : new Post();
	}
}
