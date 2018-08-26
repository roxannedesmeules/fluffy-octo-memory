import { inject, TestBed } from "@angular/core/testing";
import { BrowserModule, DomSanitizer } from "@angular/platform-browser";
import { SafeHtmlPipe } from "./safe-html.pipe";

describe("SafeHtmlPipe", () => {
    beforeEach(() => {
        TestBed.configureTestingModule({
            imports : [ BrowserModule ],
        });
    });

    it("should create an instance", inject([ DomSanitizer ], (sanitizer: DomSanitizer) => {
        const pipe = new SafeHtmlPipe(sanitizer);

        expect(pipe).toBeTruthy();
    }));

    it("should sanitize the HTML and make it safe to use", inject([ DomSanitizer ], (sanitizer: DomSanitizer) => {
        const pipe  = new SafeHtmlPipe(sanitizer);
        const value = "<h1>hello world</h1><p>this is a test</p>";

        expect(pipe.transform(value)).toEqual(sanitizer.bypassSecurityTrustHtml(value));
    }));
});
