import { Component, OnInit } from "@angular/core";
import { FormArray, FormBuilder, FormGroup, Validators } from "@angular/forms";
import { ActivatedRoute } from "@angular/router";
import { ErrorResponse } from "@core/data/error-response.model";
import { Lang } from "@core/data/languages";
import { User, UserProfile, UserProfileService, UserService } from "@core/data/users";

@Component({
	selector    : "app-user-profile",
	templateUrl : "./profile.component.html",
	styleUrls   : [ "./profile.component.scss" ],
})
export class ProfileComponent implements OnInit {

	public loading = false;

	public form: FormGroup;

	public user: User;
	public languages: Lang[] = [];

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
				  private service: UserProfileService,
				  private userService: UserService ) {
	}

	ngOnInit () {
		this.languages = this._route.snapshot.data[ "languages" ];
		this.user      = this._route.snapshot.data[ "user" ];

		this._createForm();
	}

	/**
	 * Create the Form group to be used in the profile page.
	 *
	 * @private
	 */
	private _createForm () {
		this.form = this._builder.group({
			firstname    : this._builder.control(this.user.profile.firstname, [ Validators.required ]),
			lastname     : this._builder.control(this.user.profile.lastname, [ Validators.required ]),
			birthday     : this._builder.control(this.user.profile.birthdayToDatepicker(), [ Validators.required ]),
			translations : this._builder.array([]),
		});

		this.languages.forEach(( lang ) => {
			const translation = this.user.profile.findTranslation(lang.icu);
			const control     = this._builder.group({
				lang_id   : this._builder.control(lang.id, [ Validators.required ]),
				job_title : this._builder.control(translation.job_title),
				biography : this._builder.control(translation.biography),
			});

			this.getTranslations().push(control);
		});
	}

	public getTranslations (): FormArray {
		return this.form.get("translations") as FormArray;
	}

	public uploadPicture ( files: FileList ) {
		this.service.uploadPicture(files[ 0 ])
			.subscribe(
					( result: UserProfile ) => {
						this.user.profile = result;

						this.userService.saveAppUser(this.user);
					},
					( error: ErrorResponse ) => {
						console.log(error);
					},
			);
	}

	/**
	 *
	 */
	public save () {
		this.loading = true;
		const body   = new UserProfile(this.form.getRawValue());

		this.service
			.update(body.form())
			.subscribe(
					( result: UserProfile ) => {
						this.loading = false;
						this.user.profile = result;

						this.userService.saveAppUser(this.user);
					},
					( error: ErrorResponse ) => {
						this.loading = false;
						console.log(error);
					},
			);
	}
}
