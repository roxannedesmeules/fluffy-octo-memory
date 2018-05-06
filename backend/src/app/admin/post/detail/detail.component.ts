import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormArray, FormGroup, Validators, FormControl } from "@angular/forms";
import { ActivatedRoute } from "@angular/router";
import { Category } from "@core/data/categories";
import { ErrorResponse } from "@core/data/error-response.model";
import { Lang } from "@core/data/languages";
import { Post, PostService, PostCoverService } from "@core/data/posts";
import { PostStatus } from "@core/data/posts/post-status.model";
import { PostTagService } from "@core/data/posts/post-tag.service";
import { Tag } from "@core/data/tags";
import { LoggerService } from "@shared/logger/logger.service";
import { AtIndexOfPipe } from "@shared/pipes/array/at-index-of.pipe";
import { SlugPipe } from "@shared/pipes/string/slug.pipe";
import { Observable } from "rxjs/Observable";

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
	public tags: Tag[];

	public form: FormGroup;
	public errors: any  = {};

	public formLoading   = false;
	public statusLoading = false;

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
				  private postTagService: PostTagService,
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
			tags           : this._builder.control([]),
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

	public displayStatusChangeBtn ( status: string ): boolean {
		if (this.isCreate()) {
			return false;
		}

		const statusId = this.atIndexOf.transform(status, this.statuses, "name", "id");

		return (this.post.post_status_id === statusId);
	}

	/**
	 *
	 * @param {Post} post
	 * @return {File[]}
	 * @private
	 */
	private _filesToUpload ( post: Post ): any[] {
		const files: any[] = [];
		const translations  = this.getTranslations().controls;

		translations.forEach((control) => {
			const file = control.get("cover").value;
			const lang = control.get("lang_id").value;

			if (file && post.findTranslation(lang)) {
				let form = new FormData();
				form.append("picture", file);

				files.push({ lang_id : lang, file : form });
			}
		});

		return files;
	}

	public getErrors ( name: string, langId?: number ): any[] {
		//  if there isn't any errors, then return an empty array
		if (Object.keys(this.errors).length === 0) {
			return [];
		}

		//  if the attribute exists as a key in the errors list, then return its content.
		if (this.errors[ name ]) {
			return this.errors[ name ];
		}

		const lang = this.getLang(langId);

		if (this.errors[ lang ] && this.errors[ lang ][ 0 ][ name ]) {
			return [ this.errors[ lang ][ 0 ][ name ] ];
		}

		return [];
	}

	/**
	 *
	 * @param langId
	 *
	 * @return {string}
	 */
	private getLang ( langId ): string {
		return this.atIndexOf.transform(langId, this.languages, "id", "icu");
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
	 * @return {}
	 * @private
	 */
	private _getTagsToUpdate () {
		const tags: any[] = this.form.get("tags").value;

		// if is create and there is tags, then they will have to be added
		if (this.isCreate()) {
			return { add : tags, delete : [] };
		}

		// otherwise, compare with existing tags and check which ones needs to be added and removed
		return this.post.compareTags(tags);
	}

	/**
	 *
	 * @param {string} name
	 * @param {number} idx
	 *
	 * @return {boolean}
	 */
	public hasError ( name: string, idx?: number) {
		let input: FormControl;
		let errors: any[] = [];

		if (idx === undefined) {
			input  = this.form.get(name) as FormControl;
			errors = this.getErrors(name);
		} else {
			const translation = this.getTranslations().at(idx);

			input  = translation.get(name) as FormControl;
			errors = this.getErrors(name, translation.get('lang_id').value);
		}

		return ((input.invalid && input.touched) || errors.length > 0);
	}

	/**
	 *
	 * @param {Post} post
	 *
	 * @return {Boolean}
	 */
	private hasFilesToUpload ( post: Post ): Boolean {
		return (this._filesToUpload(post).length > 0);
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
		this.form.get("category_id").setValue(this.post.category_id);
		this.form.get("post_status_id").setValue(this.post.post_status_id);

		this.languages.forEach((val, idx) => {
			const translation = this.post.findTranslation(val.icu);

			this.getTranslations().at(idx).get("lang_id").setValue(val.id);
			this.getTranslations().at(idx).get("cover").setValue(undefined);
			this.getTranslations().at(idx).get("cover_alt").setValue(translation.cover_alt);
			this.getTranslations().at(idx).get("title").setValue(translation.title);
			this.getTranslations().at(idx).get("slug").setValue(translation.slug);
			this.getTranslations().at(idx).get("content").setValue(translation.content);

			this.getTranslations().at(idx).get("slug").disable();
		});
	}

	/**
	 *
	 */
	public save () {
		this.errors      = [];
		this.formLoading = true;

		let req  = null;
		let body = this.post.form(this.form.getRawValue());

		if (this.isCreate()) {
			req = this.service.create(body);
		} else {
			req = this.service.update(this.post.id, body);
		}

		req.subscribe(
				(result: Post) => {
					const hasRelation = this._updateAllRelations(result);

					// reset form after create
					if (this.isCreate()) {
						this.resetForm();
					} else {
						this.post = result;
					}

					// if there isn't any relation to update show the success message
					if (!hasRelation) {
						this.formLoading = false;

						this._showSuccessMessage();
					}
				},
				(err: ErrorResponse) => {
					this.formLoading = false;
					this.errors      = err.form_error;
				},
		);
	}

	/**
	 *
	 * @private
	 */
	private _setData () {
		//  get all data from the route
		const routeLanguages  = this._route.snapshot.data[ "languages" ];
		const routeStatuses   = this._route.snapshot.data[ "statuses" ];
		const routeCategories = this._route.snapshot.data[ "categories" ];
		const routeTags       = this._route.snapshot.data[ "tags" ];
		const routePost       = this._route.snapshot.data[ "post" ];

		// assign data found or set a default value
		this.post       = routePost || new Post();
		this.languages  = routeLanguages || [];
		this.statuses   = routeStatuses || [];
		this.categories = routeCategories || [];
		this.tags       = routeTags || [];
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
	 * @private
	 */
	private _showSuccessMessage () {
		if (this.isCreate()) {
			this.logger.success("The post was successfully created");
		} else {
			this.logger.success("The post was successfully updated");
		}
	}

	/**
	 *
	 * @return {boolean}
	 * @private
	 */
	private _tagsChanged (): boolean {
		const tags = this._getTagsToUpdate();

		// check if there is any tag to add or delete
		return (tags.add.length > 0 || tags.delete.length > 0);
	}

	/**
	 *
	 * @param {Post} post
	 *
	 * @return {boolean}
	 * @private
	 */
	private _updateAllRelations ( post: Post ): boolean {
		const allRequests = [];

		//	if there are any files to upload, create upload requests
		if (this.hasFilesToUpload(post)) {
			const files = this._filesToUpload(post);

			allRequests.push(this.coverService.uploadSeveral(post.id, files));
		}

		//	if there are any tags to link/unlink, create requests
		if (this._tagsChanged()) {
			const tags = this._getTagsToUpdate();

			if (tags.add.length > 0) {
				allRequests.push(this.postTagService.linkSeveral(post.id, tags.add));
			}

			if (tags.delete.length > 0) {
				allRequests.push(this.postTagService.unlinkSeveral(post.id, tags.delete));
			}
		}

		//  if there isn't any requests, then return that there is nothing to do
		if (allRequests.length === 0) {
			return false;
		}

		//	create an observable on all requests
		Observable.forkJoin(allRequests)
				  .subscribe(
						  ( results ) => {
							  this.formLoading = false;
							  console.log(results);
						  },
						  ( err: ErrorResponse ) => {
							  this.formLoading = false;
							  console.log(err);
						  },
				  );

		//	return that are is something to do
		return true;
	}

	public updateStatus ( statusName: string ) {
		//  get the status ID related to the name passed in parameter
		const statusId = this.atIndexOf.transform(statusName, this.statuses, "name", "id");

		//  update the form post status id
		this.form.get("post_status_id").setValue(statusId);

		this.errors        = [];
		this.statusLoading = true;

		let body = this.post.form(this.form.getRawValue());

		this.service
			.update(this.post.id, body)
			.subscribe(
				(result: Post) => {
					this.post          = result;
					this.statusLoading = false;

					this._showSuccessMessage();
				},
				(err: ErrorResponse) => {
					this.statusLoading = false;
					this.errors        = err.form_error;
				},
		);
	}
}
