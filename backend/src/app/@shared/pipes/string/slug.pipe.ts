import { Pipe, PipeTransform } from "@angular/core";

@Pipe({
	name : "slug",
})
export class SlugPipe implements PipeTransform {

	transform ( value: string ): string {
		//  replace all spaces by a dash
		let slug = value.replace(/ /g, "-");

		//  remove all apostrophe
		slug = slug.replace(/'/g, "");

		//  replace all accents
		slug = slug.normalize("NFD").replace(/[\u0300-\u036f]/g, "");

		//  return a valid slug
		return slug.toLowerCase();
	}

}
