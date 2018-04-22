import { Pipe, PipeTransform } from "@angular/core";

@Pipe({
	name : "flatTranslation",
})
export class FlatTranslationPipe implements PipeTransform {

	transform ( value: any[], label: string ): any {
		const result = [];

		value.forEach((val) => {
			result.push({ id : val.id, label : val.translations[ 0 ][ label ] });
		});

		return result;
	}

}
