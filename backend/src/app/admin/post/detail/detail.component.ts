import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormArray, FormGroup, Validators, FormControl } from "@angular/forms";
import { ActivatedRoute } from "@angular/router";
import { Category } from "@core/data/categories";
import { ErrorResponse } from "@core/data/error-response.model";
import { Lang } from "@core/data/languages";
import { Post, PostService, PostCoverService } from "@core/data/posts";
import { PostStatus } from "@core/data/posts/post-status.model";
import { LoggerService } from "@shared/logger/logger.service";
import { AtIndexOfPipe } from "@shared/pipes/array/at-index-of.pipe";
import { SlugPipe } from "@shared/pipes/string/slug.pipe";

@Component({
	selector    : "app-post-detail",
	templateUrl : "./detail.component.html",
	styleUrls   : [ "./detail.component.scss" ],
})
export class DetailComponent implements OnInit {

	public title = "New post";

	public post: Post;
	public languages: Lang[];
	public statuses: PostStatus[];
	public categories: Category[];

	public form: FormGroup;
	public errors: any  = {};
	public loading      = false;

	public editorOptions: Object = {
		charCounterCount : true,
		heightMin        : 150,
		toolbarButtons   : [
			"bold", "italic", "underline", "strikeThrough",
			"|", "fontFamily", "fontSize", "color",
			"|", "paragraphFormat", "formatOL", "formatUL", "outdent", "indent", "quote",
			"-", "insertLink", "insertImage", "embedly", "insertFile", "insertTable",
			"|", "insertHR", "selectAll", "clearFormatting",
			"|", "print", "help", "html",
		],
	};

	constructor ( private _route: ActivatedRoute,
				  private _builder: FormBuilder,
				  private atIndexOf: AtIndexOfPipe,
				  private slugPipe: SlugPipe,
				  private service: PostService,
				  private coverService: PostCoverService,
				  private logger: LoggerService ) {
	}

	ngOnInit () {
		this._setData();
		this._createForm();

		if (!this.isCreate()) {
			this.title = "Update post";
		}
	}

	/**
	 *
	 * @private
	 */
	private _createForm () {
		let status = this.post.post_status_id;

		if (this.isCreate()) {
			status = this.atIndexOf.transform("draft", this.statuses, "name", "id");
		}

		this.form = this._builder.group({
			category_id    : this._builder.control(this.post.category_id, [ Validators.required ]),
			post_status_id : this._builder.control(status, [ Validators.required ]),
			translations   : this._builder.array([]),
		});

		this.languages.forEach(( lang: Lang ) => {
			const translation = this.post.findTranslation(lang.icu);
			const control = this._builder.group({
				lang_id   : this._builder.control(lang.id),
				cover     : this._builder.control(undefined),
				cover_alt : this._builder.control(translation.cover_alt),
				title     : this._builder.control(translation.title),
				slug      : this._builder.control(translation.slug),
				content   : this._builder.control(translation.content),
			});

			control.get("slug").disable();

			this.getTranslations().push(control);
		});
	}

	/**
	 * Shorthand method to easily get all translations from the form object.
	 *
	 * @return {FormArray}
	 */
	public getTranslations (): FormArray {
		return this.form.get("translations") as FormArray;
	}

	/**
	 *
	 * @param {string} controlName
	 * @param {number} translationIdx
	 * @return {any[]}
	 */
	public getErrors ( controlName: string, translationIdx?: number ): any[] {
		if (translationIdx) {

		} else {
			return this.errors[ controlName ] || [];
		}
	}

	/**
	 *
	 * @param {string} controlName
	 * @param {FormGroup} translation
	 * @return {boolean}
	 */
	public hasError ( controlName: string, translation?: FormGroup ) {
		let input: FormControl;
		let errors: any[] = [];

		if (translation) {
		} else {
			input  = this.form.get(controlName) as FormControl;
			errors = this.getErrors(controlName);
		}

		return ((input.invalid && input.touched) || errors.length > 0);
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
	 *
	 */
	public resetForm () {
		switch (this.isCreate()) {
			case false :
				break;
			case true :
				// reset the post
				this.post = new Post();

				// re create the form itself so the values are reset properly
				this._createForm();
				break;
		}
	}

	/**
	 *
	 */
	public save () {
		this.errors  = [];
		this.loading = true;

		let req  = null;
		let body = this.post.form(this.form.getRawValue());

		if (this.isCreate()) {
			req = this.service.create(body);
		} else {
			req = this.service.update(this.post.id, body);
		}

		req.subscribe(
				(result: Post) => {
					this.loading = false;

					// upload files if there are any
					this._uploadFiles(result);

					// TODO create relation between tag & post

					// reset form after create
					if (this.isCreate()) {
						this.resetForm();
					}
				},
				(err: ErrorResponse) => {
					this.loading = false;
					console.log(err);
				},
		);
	}

	/**
	 *
	 * @private
	 */
	private _setData () {
		const routeLanguages  = this._route.snapshot.data[ "languages" ];
		const routeStatuses   = this._route.snapshot.data[ "statuses" ];
		const routeCategories = this._route.snapshot.data[ "categories" ];
		const routePost       = this._route.snapshot.data[ "post" ];

		this.post       = routePost || new Post();
		this.languages  = routeLanguages || [];
		this.statuses   = routeStatuses || [];
		this.categories = routeCategories || [];
	}

	/**
	 *
	 * @param {number} translationIdx
	 */
	public setSlug ( translationIdx: number ) {
		//  get the current title
		const title = this.getTranslations().at(translationIdx).get("title").value;

		//  transform the title to remove spaces, apostrophe and transform accents
		const slug = this.slugPipe.transform(title);

		this.getTranslations().at(translationIdx).get("slug").setValue(slug);
	}

	/**
	 *
	 * @param {Post} post
	 *
	 * @private
	 */
	private _uploadFiles ( post: Post ) {
		const translations = this.getTranslations().controls;

		translations.forEach(( control ) => {
			const file   = control.get("cover").value;
			const lang   = control.get("lang_id").value;

			//  update the file only if there is one and if the translation exists.
			if (file && post.findTranslation(lang)) {
				this._uploadFile(post.id, lang, file);
			}
		});
	}

	/**
	 *
	 * @param {number} postId
	 * @param {number} langId
	 * @param {File} file
	 *
	 * @private
	 */
	private _uploadFile ( postId: number, langId: number, file: File ) {
		let form = new FormData();
			form.append("picture", file);

		this.coverService.upload(postId, langId, form)
			.subscribe(
				(result: Post) => {
					console.log(result);
				},
				(err: ErrorResponse) => {
					console.log(err);
				}
			);
	}
}
