import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PostSummaryComponent } from './post-summary.component';

describe('PostSummaryComponent', () => {
  let component: PostSummaryComponent;
  let fixture: ComponentFixture<PostSummaryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PostSummaryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PostSummaryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
