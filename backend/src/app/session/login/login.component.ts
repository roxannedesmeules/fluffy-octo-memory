import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";

import { Toast, ToasterService } from "angular2-toaster";
import { AuthService } from "@core/data/users/auth.service";
import { UserService } from "@core/data/users/user.service";
import { UserAuthForm } from "@core/data/users/auth.form";
import { ErrorResponse } from "@core/data/error-response.model";

@Component({
	selector    : "ngx-login",
	templateUrl : "./login.component.html",
	styleUrls   : [ "./login.component.scss" ],
})
export class LoginComponent implements OnInit {

	errors: string[]   = [];
	messages: string[] = [];
	submitted: boolean = false;

	public form: FormGroup;

	constructor ( private _builder: FormBuilder,
				  private _router: Router,
				  private toastService: ToasterService,
				  private authService: AuthService,
				  private userService: UserService ) { }

	ngOnInit () {
		this._createForm();
	}

	/**
	 * Build login form group
	 *
	 * @private
	 */
	private _createForm () {
		this.form = this._builder.group({
			username : this._builder.control("", [ Validators.required ]),
			password : this._builder.control("", [ Validators.required ]),
		});
	}

	/**
	 *
	 */
	public login () {
		this._reset();

		this.submitted = true;

		if (this.form.invalid) {
			return;
		}

		this.authService
				.login(new UserAuthForm(this.form.getRawValue()))
				.then(( result: any ) => {
					this.submitted = false;

					this.userService.saveAppUser(this.authService.mapModel(result));
					// this._router.navigate([ "/" ]);
				})
				.catch(( error: ErrorResponse ) => {
					this.submitted = false;

					this.toastService.pop(<Toast> { type : "error", title : null, body : error.message });
				});
	}

	/**
	 * Reset errors and messages
	 *
	 * @private
	 */
	private _reset () {
		this.errors   = [];
		this.messages = [];
	}
}
