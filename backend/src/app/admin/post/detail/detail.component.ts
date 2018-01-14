import { Component, OnInit } from "@angular/core";
import { FormArray, FormBuilder, FormGroup, Validators } from "@angular/forms";
import { ActivatedRoute } from "@angular/router";

import { Post } from "@core/data/posts/post.model";
import { PostStatus } from "@core/data/posts/post-status.model";
import { PostService } from "@core/data/posts/post.service";
import { Category } from "@core/data/categories/category.model";
import { Lang } from "@core/data/languages/lang.model";

import "./ckeditor.loader";
import "ckeditor";
import { SlugPipe } from "@shared/pipes/string/slug.pipe";

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

	public form: FormGroup;
	public submitted = false;

	constructor ( private _route: ActivatedRoute,
				  private _builder: FormBuilder,
				  private slugPipe: SlugPipe,
				  private service: PostService ) { }

	ngOnInit () {
		this._setData();
		this._createForm();
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
		this.form = this._builder.group({
			category_id    : this._builder.control(null, [ Validators.required ]),
			post_status_id : this._builder.control(null, [ Validators.required ]),
			translations   : this._builder.array([]),
		});

		this.languages.forEach(( val, idx ) => {
			//  TODO  find existing translation
			const control = this._builder.group({
				lang_id : this._builder.control(val.id),
				title   : this._builder.control("", [ Validators.required ]),
				slug    : this._builder.control("", [ Validators.required ]),
				content : this._builder.control("", [ Validators.required ]),
			});

			this.getTranslations().push(control);
		});
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

	public save () {
		console.log(this.form.getRawValue());
		this.submitted = true;
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

	/**
	 *
	 * @param {string} field
	 * @param {number} idx
	 *
	 * @return {boolean}
	 */
	public showErrors ( field: string, idx?: number ): boolean {
		let control = this.form.get(field);

		if (typeof idx !== "undefined") {
			control = this.getTranslations().at(idx).get(field);
		}

		return ((this.submitted || control.touched) && control.invalid);
	}
}
