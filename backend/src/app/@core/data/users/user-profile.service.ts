import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { BaseService } from "@core/data/base.service";
import { UserProfile } from "@core/data/users/user-profile.model";

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
				   .map(( res: any ) => {
					   if (BaseService.SUCCESS_CODES.indexOf(res.status) >= 0) {
						   return this.mapListToModelList(res);
					   } else {
						   return this.mapError(res);
					   }
				   });
	}

	public uploadPicture ( picture: File ) {
		const body = new FormData();
		body.append("picture", picture, picture.name);

		return this.http.post(this._url("picture"), body)
				   .map(( res: any ) => {
					   if (BaseService.SUCCESS_CODES.indexOf(res.status) >= 0) {
						   return this.mapListToModelList(res);
					   } else {
						   return this.mapError(res);
					   }
				   });
	}

	// TODO implement update password
}
