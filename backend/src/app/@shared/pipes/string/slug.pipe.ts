import { Pipe, PipeTransform } from "@angular/core";

@Pipe({
	name : "slug",
})
export class SlugPipe implements PipeTransform {

	transform ( value: string ): string {
		//  replace all spaces by a dash
		let slug = value.replace(" ", "-");

		//  remove all apostrophe
		slug = slug.replace("'", "");

		//  replace all accents
		slug = slug.normalize("NFD").replace(/[\u0300-\u036f]/g, "");

		//  return a valid slug
		return slug;
	}

}
