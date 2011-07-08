<h1><?php echo __('Payment method', null, 'ullCourseMessages') ?></h1>

<p>
  <?php echo __('Please select a payment method', null, 'ullCourseMessages') ?>
</p>

<div class="ull_course_payment_online">
  <?php echo link_to(
    __('I want to book online using "Sofortüberweisung"'),
    'ullCourse/paymentSofortueberweisung?slug=' . $doc['slug']
  ) ?>

</div>

<div class="ull_course_payment_invoice">
    <?php echo link_to(
    __('I want to pay on invoice'),
    'ullCourse/paymentInvoice?slug=' . $doc['slug']
  ) ?>
</div>