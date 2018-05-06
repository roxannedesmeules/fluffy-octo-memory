import { Pipe, PipeTransform } from "@angular/core";

@Pipe({
	name : "slug",
})
export class SlugPipe implements PipeTransform {

	transform ( value: string ): string {
		let slug = value;

		//  remove all punctuations
		slug = slug.replace(/[.,'\/#!$%\^&\*;:{}=\-_`~()]/g, "");

		//  replace all accents
		slug = slug.normalize("NFD").replace(/[\u0300-\u036f]/g, "");

		//  remove all extra spaces
		slug = slug.replace(/\s{2,}/g," ");

		//  remove space if last character
		slug = slug.replace(/\s+$/, '');

		//  replace all spaces by a dash
		slug = slug.replace(/ /g, "-");

		//  return a valid slug
		return slug.toLowerCase();
	}

}
