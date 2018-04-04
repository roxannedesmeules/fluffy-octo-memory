import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { BaseService } from "@core/data/base.service";
import { UserProfile } from "@core/data/users/user-profile.model";
import { Observable } from "rxjs/Observable";
import { catchError, map } from "rxjs/operators";

@Injectable()
export class UserProfileService extends BaseService {
	public baseUrl = "user";
	public modelName = "me";

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => new UserProfile(construct);
	}

	public update ( body: any ) {
		return this.http.put(this._url(), body)
				   .pipe(
						   map(( res: any ) => this.mapModel(res)),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}

	public uploadPicture ( picture: File ) {
		const body = new FormData();
		body.append("picture", picture, picture.name);

		return this.http.post(this._url("picture"), body)
				   .pipe(
						   map(( res: any ) => this.mapModel(res)),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}

	// TODO implement update password
}
