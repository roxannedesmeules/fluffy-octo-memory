import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ItemLinkComponent } from './item-link.component';

describe('ItemLinkComponent', () => {
  let component: ItemLinkComponent;
  let fixture: ComponentFixture<ItemLinkComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ItemLinkComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ItemLinkComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
